let lastScroll = 0;

const bottomNav = document.querySelector('.bottom-nav');

window.addEventListener('scroll', () => {

    const currentScroll = window.pageYOffset;

    if (currentScroll > lastScroll && currentScroll > 120) {

        bottomNav.classList.add('hide-nav');

    } else {

        bottomNav.classList.remove('hide-nav');

    }

    lastScroll = currentScroll;

});


const megaLinks = document.querySelectorAll('.mega-link');

const megaPanels = document.querySelectorAll('.mega-panel');

megaLinks.forEach(link => {

    link.addEventListener('mouseenter', () => {

        megaLinks.forEach(item => {
            item.classList.remove('active');
        });

        megaPanels.forEach(panel => {
            panel.classList.remove('active');
        });

        link.classList.add('active');

        const target = link.dataset.target;

        document
            .getElementById(target)
            .classList.add('active');

    });

});