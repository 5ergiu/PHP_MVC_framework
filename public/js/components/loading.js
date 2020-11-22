export default class Loading {

    #spinner
    #content

    show = (spinner, content) => {
        this.#spinner = spinner
        this.#content = content
        this.#spinner.classList.remove('hide')
        this.#content.parentElement.classList.add('overlay')
    }

    hide = (timeout = null) => {
        if (timeout) {
            setTimeout(() => {
                this.#content.parentElement.classList.remove('overlay')
                this.#spinner.classList.add('hide')
            }, timeout)
        } else {
            this.#content.parentElement.classList.remove('overlay')
            this.#spinner.classList.add('hide')
        }
    }
}
