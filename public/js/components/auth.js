import ROUTES from './routes.js'
import Loading from './loading.js'

export default class Auth {

    #Loading
    #Notification
    #Utils
    isLoggedIn

    constructor(isLoggedIn, Notification, Utils) {
        this.isLoggedIn = isLoggedIn
        this.#Loading = new Loading
        this.#Notification = Notification
        this.#Utils = Utils
        this.authListener(this.isLoggedIn)
    }

    authListener = isLoggedIn => {
        if (!isLoggedIn) {
            let login = document.getElementById('js-login')
            let loginMessage = document.getElementById('js-login-message')
            login.querySelectorAll('.form__input').forEach(item => {
                item.addEventListener('input', () => {
                    if (loginMessage.classList.contains('errors')) {
                        loginMessage.classList.remove('errors')
                        loginMessage.innerHTML = 'Hello there! 👋'
                    }
                })
            })
            login.addEventListener('submit', event => {
                event.preventDefault()
                this.#Loading.show(document.getElementById('js-login-spinner'), login)
                this.#Utils.serializeForm(login).fetchJsonData(ROUTES.LOGIN)
                    .then(data => {
                        if (data.response) {
                            localStorage.setItem('isLoggedIn', 'yes');
                            window.location.replace(data.response.redirect.path)
                        } else {
                            this.#Loading.hide()
                            loginMessage.classList.add('errors')
                            loginMessage.innerHTML = data.errors.credentials
                            this.#Notification.show({
                                message: data.errors.credentials,
                                errors: true,
                            })
                        }
                    })
            })
        }
    }
}
