import ROUTES from '../components/routes.js'

export default class Auth {

    constructor(Utils) {
        // this.Loading = Loading
        this.Utils = Utils
        this.loginForm = document.getElementById('login')
        // this.registerForm = document.querySelector('.register__form')
        this.addEventListeners()
    }

    // adding event listeners for the login/register forms.
    addEventListeners = () => {
        // if (this.registerForm !== null) {
        //     this.registerForm.addEventListener('submit', () => {
        //         this.Loading.show()
        //     })
        // }
        if (this.loginForm !== null) {
            this.loginForm.addEventListener('submit', event => {
                event.preventDefault()
                this.Utils.serializeForm(this.loginForm).fetchJsonData(ROUTES.LOGIN)
                    .then(data => {
                        console.log(data)
                    })
                // this.Loading.show()
            })
        }
    }
}
