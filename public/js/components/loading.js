/**
 * This class should be instantiated individually on each class where is needed so each request
 * can have it's 'loading' instance dealing with it in case they're multiple requests at the
 * same time, f.ex: in article/read, we can bookmark and like at the same time, we need separate
 * instances so that multiple loading could be used and not get stuck.
 */
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
