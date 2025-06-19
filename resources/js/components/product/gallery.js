export const initGallery = () => {
    let activeImage = 0;
    const images = document.querySelectorAll('.product-gallery-image');
    const thumbnails = document.querySelectorAll('.product-gallery-thumbnail');

    const setActiveImage = (index) => {
        images.forEach(img => img.classList.remove('active'));
        thumbnails.forEach(thumb => thumb.classList.remove('active'));
        
        images[index].classList.add('active');
        thumbnails[index].classList.add('active');
        activeImage = index;
    };

    const initZoom = () => {
        images.forEach(image => {
            image.addEventListener('mousemove', (e) => {
                const { left, top, width, height } = image.getBoundingClientRect();
                const x = (e.clientX - left) / width * 100;
                const y = (e.clientY - top) / height * 100;
                
                image.style.transformOrigin = `${x}% ${y}%`;
            });

            image.addEventListener('mouseenter', () => {
                image.style.transform = 'scale(1.5)';
            });

            image.addEventListener('mouseleave', () => {
                image.style.transform = 'scale(1)';
            });
        });
    };

    thumbnails.forEach((thumb, index) => {
        thumb.addEventListener('click', () => setActiveImage(index));
    });

    // Инициализация зума
    initZoom();

    // Публичные методы
    return {
        next: () => setActiveImage((activeImage + 1) % images.length),
        prev: () => setActiveImage((activeImage - 1 + images.length) % images.length),
        setImage: (index) => setActiveImage(index)
    };
};