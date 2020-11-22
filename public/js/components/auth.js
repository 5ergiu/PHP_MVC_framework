import ROUTES from './routes.js'

export default class Auth {

    #Loading
    #Notification
    #Utils
    isLoggedIn = false

    constructor(Loading, Notification, Utils, isLoggedIn) {
        this.#Loading = Loading
        this.#Notification = Notification
        this.#Utils = Utils
        this.isLoggedIn = isLoggedIn
        this.authListener(this.isLoggedIn)
    }

    authListener = isLoggedIn => {
        if (isLoggedIn) {
            let logout = document.getElementById('js-logout')
            logout.addEventListener('click', () => {
                localStorage.removeItem('isLoggedIn')
                this.#Loading.show(document.getElementById('js-logout-loading-spinner'), document.getElementById('js-navigation-user'))
                window.location.replace('/auth/logout')
            })
        } else {
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
                this.#Loading.show(document.getElementById('js-login-loading-spinner'), login)
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
                                imgPath: ROUTES.ERROR_IMAGE,
                            })
                        }
                    })
            })
        }
    }
}
