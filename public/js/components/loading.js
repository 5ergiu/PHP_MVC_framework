/**
 This class will be used to show a loading spinner and modify the content
 that's being changed accordingly.
 */
export default class Loading {

    constructor(
        spinner = document.getElementById('main-spinner'),
        content = document.getElementById('main-content')
    ) {
        this.spinner = spinner
        this.content = content
    }

    /**
     |--------------------------------------------------------------------------
     | Show method
     |--------------------------------------------------------------------------
     |
     | This method changes the default display style for the spinner, which by
     | default has display = 'none'; and will change the content accordingly.
     |
     */
    show = () => {
        this.spinner.classList.remove('hide');
        this.content.classList.add('busy');
    }

    /**
     |--------------------------------------------------------------------------
     | Hide method
     |--------------------------------------------------------------------------
     |
     | This method will hide the spinner immediately if there is no 'timeout'
     | passed, but if there is, the spinner will be hidden after that; and
     | the content will be changed back.
     |
     */
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
