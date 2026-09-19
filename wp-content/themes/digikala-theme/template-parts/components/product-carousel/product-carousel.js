const wrapper = document.querySelector('.carousel-products');

document.querySelector('.next').onclick = () => {

    wrapper.scrollBy({
        left:220,
        behavior:'smooth'
    });

};

document.querySelector('.prev').onclick = () => {

    wrapper.scrollBy({
        left:-220,
        behavior:'smooth'
    });

};