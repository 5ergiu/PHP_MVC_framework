import ROUTES from './routes.js';
/**
 This class will deal with commomn user actions, such as 'likes', 'adding'
 a post to their read list, etc.
 */
export default class Actions {

    constructor(Utils, Notification) {
        this.Utils = Utils
        this.Notification = Notification
        this.bookmarksCount = document.getElementById('bookmarksCount')
        document.querySelectorAll('.button--like').forEach(item => {
            this.like(item)
        })
        document.querySelectorAll('.button--bookmark').forEach(item => {
            this.bookmark(item)
        })
    }

    like = (item) => {
        item.addEventListener('click', () => {
            let data = {
                article: item.dataset.articleId,
                table: 'likes',
                column: 'liked_by',
            }
            if (item.classList.contains('liked')) {
                this.Utils.fetchJsonData(ROUTES.USER_REMOVE, data)
                  .then(data => { console.log(parseInt(item.nextElementSibling.innerHTML))
                    if (data.result) {
                        item.classList.remove('liked')
                        item.setAttribute('title', 'Like article')
                        let likes = parseInt(item.nextElementSibling.innerHTML)
                        if (likes > 0) {
                            item.nextElementSibling.innerHTML = likes - 1
                        }
                        this.disableButton(item)
                    } else {
                        this.Notification.show({
                            isPrompt: true,
                            message: data.message,
                            imgPath: ROUTES.ERROR_IMAGE,
                        })
                    }
                })
            } else {
                this.Utils.fetchJsonData(ROUTES.USER_ADD, data)
                  .then(data => {
                    if (data.result) {
                        item.setAttribute('title', 'Unlike article')
                        item.classList.add('liked')
                        item.classList.add('animate')
                        let likes = parseInt(item.nextElementSibling.innerHTML)
                        item.nextElementSibling.innerHTML = likes + 1
                        setTimeout(() => {
                            item.classList.remove('animate')
                        }, 2000)
                    } else {
                        this.Notification.show({
                            isPrompt: true,
                            message: data.message,
                            imgPath: ROUTES.ERROR_IMAGE,
                        })
                    }
                })
            }
        })
    }

    bookmark = (item) => {
        item.addEventListener('click', () => {
            let data = {
                article: item.dataset.articleId,
                table: 'bookmarks',
                column: 'bookmarked_by',
            }
            if (item.classList.contains('bookmarked')) {
                this.Utils.fetchJsonData(ROUTES.USER_REMOVE, data)
                  .then(data => {
                      if (data.result) {
                          item.setAttribute('title', 'Bookmark article')
                          item.classList.remove('bookmarked')
                          let bookmarksCount = parseInt(this.bookmarksCount.innerHTML)
                          if (bookmarksCount > 0) {
                              this.bookmarksCount.innerHTML = bookmarksCount - 1
                          }
                          this.disableButton(item)
                          if (item.classList.contains('button--bookmark--mini')) {
                              item.getElementsByTagName('i')[0].classList.replace('fas', 'far')
                          } else {
                              item.innerText = 'SAVE'
                          }
                      } else {
                          this.Notification.show({
                              isPrompt: true,
                              message: data.message,
                              imgPath: ROUTES.ERROR_IMAGE,
                          })
                      }
                  })
            } else {
                this.Utils.fetchJsonData(ROUTES.USER_ADD, data)
                  .then(data => {
                      if (data.result) {
                          item.setAttribute('title', 'Remove bookmark')
                          item.classList.add('bookmarked')
                          let bookmarksCount = parseInt(this.bookmarksCount.innerHTML)
                          this.bookmarksCount.innerHTML = bookmarksCount + 1
                          if (item.classList.contains('button--bookmark--mini')) {
                              item.getElementsByTagName('i')[0].classList.replace('far', 'fas')
                          } else {
                              item.innerText = 'SAVED'
                          }
                      } else {
                          this.Notification.show({
                              isPrompt: true,
                              message: data.message,
                              imgPath: ROUTES.ERROR_IMAGE,
                          })
                      }
                  })
            }
        })
    }

    disableButton = (item) => {
        item.disabled = true
        setTimeout(() => {
            item.disabled = false
        }, 10000)
    }
}
