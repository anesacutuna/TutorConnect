<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ReviewDao.php';
require_once __DIR__ . '/../dao/BookingDao.php';
require_once __DIR__ . '/../../utils/Validator.php';
class ReviewService extends BaseService
{
    private BookingDao $bookings;
    public function __construct(?ReviewDao $dao = null, ?BookingDao $bookings = null)
    {
        $dao ??= new ReviewDao(); parent::__construct($dao); $this->bookings = $bookings ?? new BookingDao();
    }
    protected function validate(array $data, ?array $current = null, bool $replace = false): array
    {
        Validator::only($data, ['booking_id', 'rating', 'comment']);
        if (!$current || $replace) Validator::required($data, ['booking_id', 'rating', 'comment']);
        $candidate = array_merge($current ?? [], $data); $out = [];
        if (array_key_exists('booking_id', $data)) $out['booking_id'] = Validator::integer($data['booking_id'], 'booking_id');
        $bookingId = (int) ($candidate['booking_id'] ?? 0);
        $booking = $this->bookings->get_by_id($bookingId);
        if (!$booking) throw new ValidationException('Booking does not exist.');
        if ($booking['status'] !== 'completed') throw new ValidationException('A review is allowed only for a completed booking.');
        $existing = $this->dao->get_by_booking_id($bookingId);
        if ($existing && (!$current || (int) $existing['id'] !== (int) $current['id'])) throw new ValidationException('This booking already has a review.');
        if (array_key_exists('rating', $data)) $out['rating'] = Validator::integer($data['rating'], 'rating', 1, 5);
        if (array_key_exists('comment', $data)) {
            $comment = Validator::text($data['comment'], 'comment', 2, 2000); Validator::no_html($comment, 'comment'); $out['comment'] = $comment;
        }
        return $out;
    }
}
