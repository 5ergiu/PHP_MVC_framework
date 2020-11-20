/**
 * This class will be used to show a loading spinner and modify the content
 * that's being changed accordingly.
 */
export default class Loading {

    constructor(
        spinner = document.getElementById('main-spinner'),
        content = document.getElementById('main-content')
    ) {
        this.spinner = spinner
        this.content = content
    }

    show = () => {
        this.spinner.classList.remove('hide');
        this.content.classList.add('busy');
    }

    hide = (timeout = null) => {
        if (timeout) {
            setTimeout(() => {
                this.content.classList.remove('busy');
                this.spinner.classList.add('hide');
            }, timeout)
        } else {
            this.content.classList.remove('busy');
            this.spinner.classList.add('hide');
        }
    }
}
