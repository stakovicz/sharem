import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ['button']

  connect() {
    super.connect();
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      this.apply('dark')
    } else {
      this.apply('light')
    }
  }

  toggle() {
    if (document.body.classList.contains('dark')) {
      this.apply('light')
    } else {
      this.apply('dark')
    }
  }

  apply(theme) {
    document.body.classList.remove(theme === 'light' ? 'dark' : 'light')
    document.body.classList.add(theme)
    localStorage.theme = theme
    this.buttonTarget.innerHTML = this.buttonTarget.dataset[theme];
  }
}
