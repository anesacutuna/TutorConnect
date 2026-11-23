<?php
require_once __DIR__ . '/../../utils/exceptions.php';
require_once __DIR__ . '/../../utils/Validator.php';
require_once __DIR__ . '/../../utils/Logger.php';
class BookResourceService
{
    private string $base_url;
    private int $timeout;
    private int $connect_timeout;
    private string $user_agent;

    public function __construct(array $config)
    {
        $this->base_url = rtrim($config['open_library_base_url'] ?? 'https://openlibrary.org', '/');
        $this->timeout = max(1, (int) ($config['open_library_timeout'] ?? 8));
        $this->connect_timeout = max(1, (int) ($config['open_library_connect_timeout'] ?? 3));
        $this->user_agent = $config['open_library_user_agent'] ?? 'TutorConnect/1.0 (educational project)';
    }

    public function search(mixed $query): array
    {
        $query = Validator::text($query, 'q', 2, 100);
        $url = $this->base_url . '/search.json?' . http_build_query([
            'q' => $query,
            'limit' => 8,
            'fields' => 'key,title,author_name,first_publish_year,cover_i',
        ]);
        Logger::info('Open Library request started', ['query' => $query]);

        try {
            $handle = curl_init($url);
            if ($handle === false) throw new RuntimeException('Could not initialize cURL.');
            curl_setopt_array($handle, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => $this->connect_timeout,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_USERAGENT => $this->user_agent,
                CURLOPT_HTTPHEADER => ['Accept: application/json'],
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
            ]);
            $body = curl_exec($handle);
            $status = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
            $curl_error = curl_error($handle);
            curl_close($handle);
            if ($body === false || $status !== 200) {
                throw new RuntimeException('Open Library HTTP ' . $status . ($curl_error ? ': ' . $curl_error : ''));
            }

            $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
            if (!isset($data['docs']) || !is_array($data['docs'])) throw new RuntimeException('Open Library returned an unexpected response.');
            $books = [];
            foreach ($data['docs'] as $book) {
                if (!is_array($book) || !is_string($book['title'] ?? null)) continue;
                $key = $book['key'] ?? '';
                if (!is_string($key) || !preg_match('~^/works/OL[0-9]+W$~D', $key)) continue;
                $author_values = is_array($book['author_name'] ?? null) ? $book['author_name'] : [];
                $authors = array_values(array_filter($author_values, 'is_string'));
                $cover = isset($book['cover_i']) ? 'https://covers.openlibrary.org/b/id/' . (int) $book['cover_i'] . '-M.jpg' : null;
                $books[] = [
                    'title' => $book['title'],
                    'authors' => $authors,
                    'first_publish_year' => isset($book['first_publish_year']) ? (int) $book['first_publish_year'] : null,
                    'cover_url' => $cover,
                    'open_library_url' => 'https://openlibrary.org' . $key,
                ];
            }
            Logger::info('Open Library request completed', ['result_count' => count($books)]);
            return ['query' => $query, 'count' => count($books), 'books' => $books, 'source' => 'Open Library'];
        } catch (Throwable $error) {
            Logger::error('Open Library request failed', ['type' => get_class($error), 'detail' => $error->getMessage()]);
            throw new HttpException('Book search is temporarily unavailable. Please try again later. TutorConnect remains available.', 500);
        }
    }
}
