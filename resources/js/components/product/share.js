export const initShare = (options = {}) => {
    const {
        title = document.title,
        url = window.location.href,
        description = '',
        image = ''
    } = options;

    const shareButtons = document.querySelectorAll('.share-button');
    
    if (!shareButtons.length) {
        console.error('Share buttons not found');
        return null;
    }

    const sharers = {
        facebook: () => {
            const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
            window.open(shareUrl, 'share-facebook', 'width=650,height=450');
        },
        twitter: () => {
            const shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`;
            window.open(shareUrl, 'share-twitter', 'width=550,height=450');
        },
        telegram: () => {
            const shareUrl = `https://t.me/share/url?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`;
            window.open(shareUrl, 'share-telegram', 'width=550,height=450');
        },
        whatsapp: () => {
            const shareUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(title + ' ' + url)}`;
            window.open(shareUrl, 'share-whatsapp', 'width=550,height=450');
        },
        email: () => {
            const subject = encodeURIComponent(title);
            const body = encodeURIComponent(`${title}\n\n${description}\n\n${url}`);
            window.location.href = `mailto:?subject=${subject}&body=${body}`;
        },
        copyLink: () => {
            navigator.clipboard.writeText(url).then(() => {
                showNotification('Ссылка скопирована');
            }).catch(err => {
                console.error('Failed to copy link:', err);
                showNotification('Не удалось скопировать ссылку', 'error');
            });
        }
    };

    const showNotification = (message, type = 'success') => {
        const event = new CustomEvent('show-notification', {
            detail: { message, type }
        });
        document.dispatchEvent(event);
    };

    // Добавляем обработчики для кнопок
    shareButtons.forEach(button => {
        const platform = button.dataset.platform;
        if (sharers[platform]) {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                sharers[platform]();
                
                // Отправляем событие о шаринге
                const event = new CustomEvent('product-shared', {
                    detail: { platform, url, title }
                });
                document.dispatchEvent(event);
            });
        }
    });

    // Публичные методы
    return {
        share: (platform) => {
            if (sharers[platform]) {
                sharers[platform]();
                return true;
            }
            return false;
        },
        getShareUrl: (platform) => {
            switch (platform) {
                case 'facebook':
                    return `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                case 'twitter':
                    return `https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`;
                case 'telegram':
                    return `https://t.me/share/url?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`;
                case 'whatsapp':
                    return `https://api.whatsapp.com/send?text=${encodeURIComponent(title + ' ' + url)}`;
                default:
                    return url;
            }
        }
    };
};