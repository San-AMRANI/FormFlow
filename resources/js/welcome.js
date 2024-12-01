import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Animation configurations
const ANIMATION_CONFIG = {
    duration: '0.5s',
    delay: 100,
    initialOpacity: '0',
    initialTransform: 'translateY(20px)',
    finalOpacity: '1',
    finalTransform: 'translateY(0)'
};

// DOM Elements
document.addEventListener('DOMContentLoaded', () => {
    const elements = {
        getStartedBtn: document.getElementById('getStarted'),
        featureCards: document.querySelectorAll('.feature-card')
    };

    // Event Handlers
    function handleGetStartedClick() {
        console.log('Get Started clicked!');
        // Add navigation logic or additional actions here
    }

    function animateFeatureCards() {
        elements.featureCards.forEach((card, index) => {
            // Set initial state
            card.style.opacity = ANIMATION_CONFIG.initialOpacity;
            card.style.transform = ANIMATION_CONFIG.initialTransform;

            // Animate with delay
            setTimeout(() => {
                card.style.transition = `all ${ANIMATION_CONFIG.duration} ease`;
                card.style.opacity = ANIMATION_CONFIG.finalOpacity;
                card.style.transform = ANIMATION_CONFIG.finalTransform;
            }, ANIMATION_CONFIG.delay * index);
        });
    }

    // Event Listeners
    if (elements.getStartedBtn) {
        elements.getStartedBtn.addEventListener('click', handleGetStartedClick);
    }
    animateFeatureCards();
});