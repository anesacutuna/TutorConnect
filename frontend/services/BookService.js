'use strict';

// Frontend service: makes the AJAX request to TutorConnect's REST API.
window.BookService = {
  async search(query) {
    const endpoint = new URL('../api/v1/books', window.location.href);
    endpoint.searchParams.set('q', query);
    const response = await fetch(endpoint, { headers: { Accept: 'application/json' } });
    let payload;
    try {
      payload = await response.json();
    } catch {
      throw new Error('Book search is temporarily unavailable. Please try again later.');
    }
    if (!response.ok) {
      throw new Error(payload.error || 'Book search is temporarily unavailable. Please try again later.');
    }
    return payload;
  }
};
