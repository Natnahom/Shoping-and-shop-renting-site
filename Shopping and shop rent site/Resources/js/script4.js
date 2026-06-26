function filterReviews() {
    const selectedRating = document.getElementById('ratingFilter').value;
    const reviews = document.querySelectorAll('.review-item');

    reviews.forEach(review => {
        const reviewRating = review.getAttribute('data-rating');
        if (selectedRating === 'All' || reviewRating === selectedRating) {
            review.style.display = 'block'; // Show the review
        } else {
            review.style.display = 'none'; // Hide the review
        }
    });
}