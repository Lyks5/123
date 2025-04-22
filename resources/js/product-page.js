/**
 * Эко-магазин: функциональные JS для страницы продукта
 * Поддерживает табы, выбор количества, рейтинги, избранное, смену изображения.
 */
window.EcoStoreProductPage = (() => {
    function openTab(tabName) {
      document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
      });
      document.querySelectorAll('[role="tab"]').forEach(tab => {
        tab.classList.remove('border-eco-600', 'text-eco-900');
        tab.classList.add('border-transparent', 'text-eco-600');
        tab.setAttribute('aria-selected', 'false');
      });
      document.getElementById('content-' + tabName).classList.remove('hidden');
      const selectedTab = document.getElementById('tab-' + tabName);
      selectedTab.classList.remove('border-transparent', 'text-eco-600');
      selectedTab.classList.add('border-eco-600', 'text-eco-900');
      selectedTab.setAttribute('aria-selected', 'true');
    }
  
    function setRating(rating) {
      document.getElementById('rating').value = rating;
      document.querySelectorAll('.rating-star').forEach((star, index) => {
        if (index < rating) {
          star.classList.remove('text-gray-300');
          star.classList.add('text-yellow-400');
        } else {
          star.classList.remove('text-yellow-400');
          star.classList.add('text-gray-300');
        }
      });
    }
  
    function incrementQuantity() {
      const quantityInput = document.getElementById('quantity');
      quantityInput.value = parseInt(quantityInput.value) + 1;
    }
  
    function decrementQuantity() {
      const quantityInput = document.getElementById('quantity');
      const currentValue = parseInt(quantityInput.value);
      if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
      }
    }
  
    function updateMainImage(thumbnailImg) {
      const mainImage = document.getElementById('main-product-image');
      mainImage.src = thumbnailImg.getAttribute('data-image');
    }
  
    function addToWishlist(productId) {
      fetch('/wishlist/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Товар добавлен в избранное');
        } else {
          alert(data.message || 'Ошибка при добавлении товара в избранное');
        }
      })
      .catch(() => {
        alert('Произошла ошибка при добавлении товара в избранное');
      });
    }
  
    function shareProduct(productName) {
      if (navigator.share) {
        navigator.share({
          title: productName,
          text: "Посмотрите этот товар: " + productName,
          url: window.location.href,
        }).catch(() => {});
      } else {
        alert('Функция "Поделиться" недоступна в вашем браузере');
      }
    }
  
    function init(productName, productId) {
      openTab('description');
      setRating(5);
      document.querySelectorAll('[role="tab"]').forEach(tab => {
        tab.addEventListener('click', () => openTab(tab.getAttribute('data-tab')));
      });
      document.querySelectorAll('.product-thumbnail').forEach(img =>
        img.addEventListener('click', function() { updateMainImage(this); })
      );
      const shareBtn = document.getElementById('share-btn');
      if (shareBtn) shareBtn.addEventListener('click', () => shareProduct(productName));
      const wishlistBtn = document.getElementById('wishlist-btn');
      if (wishlistBtn) wishlistBtn.addEventListener('click', () => addToWishlist(productId));
      document.getElementById('inc-qty')?.addEventListener('click', incrementQuantity);
      document.getElementById('dec-qty')?.addEventListener('click', decrementQuantity);
      document.querySelectorAll('.rating-star').forEach((star, idx) =>
        star.addEventListener('click', () => setRating(idx + 1))
      );
    }
  
    return { openTab, setRating, incrementQuantity, decrementQuantity, updateMainImage, addToWishlist, shareProduct, init };
  })();
  