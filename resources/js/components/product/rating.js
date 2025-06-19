export const initRating = (options = {}) => {
    const {
        readOnly = false,
        initialRating = 0,
        onRate = () => {}
    } = options;

    const ratingContainer = document.querySelector('.product-rating');
    const stars = document.querySelectorAll('.rating-star');
    let currentRating = initialRating;

    if (!ratingContainer || !stars.length) {
        console.error('Rating elements not found');
        return null;
    }

    const updateStars = (rating) => {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('filled');
            } else {
                star.classList.remove('filled');
            }
        });
    };

    const handleRating = (rating) => {
        if (readOnly) return;
        
        currentRating = rating;
        updateStars(rating);
        onRate(rating);
    };

    if (!readOnly) {
        stars.forEach((star, index) => {
            // Обработка наведения
            star.addEventListener('mouseenter', () => {
                updateStars(index + 1);
            });

            // Обработка клика
            star.addEventListener('click', () => {
                handleRating(index + 1);
            });
        });

        // Возвращаем исходное состояние при уходе курсора
        ratingContainer.addEventListener('mouseleave', () => {
            updateStars(currentRating);
        });
    }

    // Устанавливаем начальное состояние
    updateStars(currentRating);

    // Публичные методы
    return {
        getRating: () => currentRating,
        setRating: (rating) => handleRating(rating),
        setReadOnly: (value) => readOnly = value
    };
};

export const calculateAverageRating = (ratings) => {
    if (!ratings.length) return 0;
    const sum = ratings.reduce((acc, rating) => acc + rating, 0);
    return Math.round((sum / ratings.length) * 10) / 10;
};

export const formatRatingCount = (count) => {
    if (count >= 1000) {
        return (count / 1000).toFixed(1) + 'k';
    }
    return count.toString();
};