/** @link https://css-tricks.com/block-links-the-search-for-a-perfect-solution/#method-4-sprinkle-javascript-on-the-second-method */
export default class Clickable {
    constructor() {
        const clickableElements = document.querySelectorAll('a.js-clickable')
        clickableElements.forEach(element => {
            element.addEventListener('click', event => {
                event.stopPropagation()
            })
        })
    }
}
