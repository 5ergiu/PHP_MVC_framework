export default class Articles {
    constructor() {
        const articles = document.querySelectorAll('.article--mini')
        articles.forEach(element => {
            element.addEventListener('click', event => {
                this.handleClick(event.currentTarget)
            })
        })
    }

    handleClick = (target) => {
        let isTextSelected = window.getSelection().toString()
        if (!isTextSelected) {
            target.querySelector('.js-main-article-link').click()
        }
    }
}
