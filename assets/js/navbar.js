document.addEventListener('DOMContentLoaded', function () {
   
    const profileLink = document.querySelector('#navbarDropdown');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    if (profileLink && dropdownMenu) {
        profileLink.addEventListener('click', function (e) {
            e.preventDefault();
            profileLink.classList.toggle('active');
            dropdownMenu.classList.toggle('show'); 
        });

        
        document.addEventListener('click', function (event) {
            if (!profileLink.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove('show');
                profileLink.classList.remove('active');
            }
        });
    }

   
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', function () {
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });

    
    const slideInElements = document.querySelectorAll('.slide-in-section, .slide-in-left, .slide-in-right');

    const isElementInViewport = (el) => {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    };

    const handleSlideIn = () => {
        slideInElements.forEach(el => {
            if (isElementInViewport(el)) {
                el.classList.add('active');
            }
        });
    };

    window.addEventListener('scroll', handleSlideIn);
    handleSlideIn(); 
});
