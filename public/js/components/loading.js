export default class Loading {

    #spinner
    #content

    show = (spinner, content) => {
        this.#spinner = spinner
        this.#content = content
        this.#spinner.classList.remove('hide')
        this.#content.classList.add('overlay')
    }

    hide = (timeout = null) => {
        if (timeout) {
            setTimeout(() => {
                this.#content.classList.remove('overlay')
                this.#spinner.classList.add('hide')
            }, timeout)
        } else {
            this.#content.classList.remove('overlay')
            this.#spinner.classList.add('hide')
        }
    }
}
