import {Controller} from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ['url', 'icon']

  copy() {
    let value = this.urlTarget.dataset.url;
    navigator.clipboard.writeText(value);

    let previous = this.iconTarget.innerHTML;
    this.iconTarget.innerHTML = "<span class='p-2 h-4 inline-block'>"+this.urlTarget.dataset.text+"</span>";

    setTimeout(() => {
      this.iconTarget.innerHTML = previous;
    }, 1000)
  }
}
