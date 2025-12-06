const ua = navigator.userAgent.toLowerCase() 
const isChromium =
  !!window.chrome && /chrome|crios|crmo|edg|brave|opera|opr|vivaldi/i.test(ua) 

if (isChromium) {
  document.addEventListener('DOMContentLoaded', () => {
    document.body.classList.add('is-chromium') 
  }) 
}

const body = document.body 

function scrollActions() {
    let last = 0, ticking = false 

    function onScroll() {
        const y = window.scrollY 
        if (y <= 0) {
            body.classList.remove('scroll-up', 'scroll-down') 
        } else if (y > last) {
            body.classList.add('scroll-down') 
            body.classList.remove('scroll-up') 
        } else {
            body.classList.add('scroll-up') 
            body.classList.remove('scroll-down') 
        }
        last = y 
        ticking = false 
    }

    function handleScroll() {
        if (!ticking) {
            requestAnimationFrame(onScroll) 
            ticking = true 
        }
    }

    window.addEventListener('scroll', handleScroll, { passive: true }) 

    return () => window.removeEventListener('scroll', handleScroll) 
}
scrollActions() 

function openCustomSearchform() {
    const button = document.querySelector('.search-mobile__button')
    const searchform = document.querySelector('#custom-searchform')
    const nav = document.querySelector('#menu-primary')
   
    if (button) button.classList.toggle('active')
    if (searchform) searchform.classList.toggle('show')
    if (nav) nav.classList.toggle('hide')
}

function closeCustomSearchform() {
    const button = document.querySelector('.search-mobile__button')
    const searchform = document.querySelector('#custom-searchform')
    const nav = document.querySelector('#menu-primary')

    if (button) button.classList.toggle('active')
    if (searchform) searchform.classList.toggle('show')
    if (nav) nav.classList.toggle('hide')
}

function toggleMenuMobile() {
    const button = document.querySelector( '.menu-mobile__button' ) 
    const menu = document.querySelector( '.main-navigation' ) 
    const header = document.getElementById( 'main-header' ) || document.querySelector( 'header' ) 

    if ( ! button || ! menu ) return 

    const isOpen = menu.classList.toggle( 'open' ) 
    button.classList.toggle( 'active', isOpen ) 
    body.style.overflow = isOpen ? 'hidden' : '' 

    const handleClickOutside = ( e ) => {
        const target = e.target 
        const clickedInsideMenu = menu.contains( target ) 
        const clickedInsideHeader = header && header.contains( target ) 
        const clickedToggleButton = button.contains( target ) 

        if ( ! clickedInsideMenu && ! clickedInsideHeader && ! clickedToggleButton ) {
            closeMenuMobile() 
            document.removeEventListener( 'click', handleClickOutside ) 
        }
    } 

    document.removeEventListener( 'click', handleClickOutside ) 
    if ( isOpen ) {
        document.addEventListener( 'click', handleClickOutside ) 
    }
}

function menuWithChildren() {
    const header = document.querySelector('header')
    if (!header) return

    const menuItems = header.querySelectorAll('.menu-item-has-children')

    menuItems.forEach(item => {
        item.addEventListener('click', e => {
            if (e.target.tagName === 'A') return

            e.preventDefault()
            item.classList.toggle('open')

            const subMenu = item.querySelector('.sub-menu')
            if (subMenu) {
                const childrenCount = subMenu.children.length
                const transitionTime = childrenCount * 0.1
                subMenu.style.transition = `max-height ${transitionTime}s ease-in-out`
                subMenu.classList.toggle('open')
            }
        })
    })
}

document.addEventListener('DOMContentLoaded', menuWithChildren)

function closeMenuMobile() {
    const button = document.querySelector('.menu-mobile__button') 
    const menu = document.querySelector('.main-navigation') 
    const body = document.body 

    if ( button ) {
        button.classList.remove('active') 
    }
    if ( menu ) {
        menu.classList.remove('show') 
        menu.classList.remove('open') 
    }

    if ( body ) {
        body.style.overflow = '' 
    }
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' || event.key === 'Esc') {
        if (typeof closeMenuMobile === 'function') {
            closeMenuMobile() 
        }
    }
}) 