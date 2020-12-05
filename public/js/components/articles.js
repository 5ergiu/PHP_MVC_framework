export default class Articles {
    constructor() {
        const articles = document.querySelectorAll('.article--mini')
        articles.forEach(element => {
            element.addEventListener('click', event => {
                this.handleClick(event.currentTarget)
            })
        })
        this.autoResize()
    }

    handleClick = (target) => {
        let isTextSelected = window.getSelection().toString()
        if (!isTextSelected) {
            target.querySelector('.js-main-article-link').click()
        }
    }

    // used to autoresize the article content(textarea) to have a much nicer appearance
    autoResize = () => {
        const articleContent = document.getElementById('js-article-content')
        if (articleContent) {
            articleContent.addEventListener('input', () => {
                articleContent.style.height = 'auto'
                articleContent.style.height = articleContent.scrollHeight + 'px'
            })
        }
    }
}
