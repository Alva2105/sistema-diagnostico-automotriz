document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.service-panel').forEach(panel => {
        const video = panel.querySelector('.hover-video');
        panel.addEventListener('mouseenter', () => {
            video.currentTime = 0;
            video.play();
            video.style.opacity = "1";
        });
        panel.addEventListener('mouseleave', () => {
            video.pause();
            video.currentTime = 0;
            video.style.opacity = "0";
        });
    });
});