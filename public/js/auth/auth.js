/**
 Used to show loading for now.
 */
export default class Auth {

    constructor(Loading) {
        this.Loading = Loading
        this.loginForm = document.querySelector('.navigation__login__form')
        this.registerForm = document.querySelector('.register__form')
        this.addEventListeners()
    }

    // adding event listeners for the login/register forms.
    addEventListeners = () => {
        if (this.registerForm !== null) {
            this.registerForm.addEventListener('submit', () => {
                this.Loading.show()
            })
        }
        if (this.loginForm !== null) {
            this.loginForm.addEventListener('submit', event => {
                this.Loading.show()
            })
        }
    }
}
