/**
 This class will be used to create common dropdowns. It can also deal
 with multiple dropdowns as it checks for all 'dropdown__toggler' classes.
 */
export default class Dropdown {
    constructor() {
        const dropdownTogglers = document.querySelectorAll('.dropdown__toggler')
        dropdownTogglers.forEach(element => {
            element.addEventListener('click', event => {
                this.open(event)
            })
        })
    }

    /**
     * In the constructor, a 'click' event listener is set to all 'dropdown__toggler'
     * classes, but it takes the 'nextElementSibling' of the 'currentTarget' for
     * the event(the event that triggered the listener) so it shows the
     * correct dropdown.
     * Also there's another 'click' event listener added to the document so we can
     * check when the user clicks outside the dropdown so it can be closed.
     */
    open = event => {
        /** @link https://www.blustemy.io/detecting-a-click-outside-an-element-in-javascript/ */
        let dropdownContent = event.currentTarget.nextElementSibling
        let dropdown = dropdownContent.closest('.dropdown')
        dropdownContent.classList.toggle('show')
        document.addEventListener('click', event => {
            let target = event.target
            do {
                // click inside
                if (target === dropdown) {
                    return;
                }
                // Go up the DOM
                target = target.parentNode;
            } while (target);
            // click outside
            dropdownContent.classList.remove('show')
        })
    }
}
