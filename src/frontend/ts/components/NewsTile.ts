import {News} from "../model/news";

type OnEditHandler = (news: News) => void

export class NewsTile {
    private readonly $root: HTMLElement;
    private readonly news: News;
    private readonly onEditHandler: ((news: News) => void) | undefined;
    constructor($root: HTMLElement, onEditHandler?: OnEditHandler) {
        this.$root = $root;
        this.news = {
            id: Number($root.getAttribute('data-news-id')),
            title: $root.querySelector('.js-news-title')?.textContent as string,
            content: $root.querySelector('.js-news-content')?.textContent as string,
        };
        this.onEditHandler = onEditHandler;

        this.initEventListeners();
    }

    public static autoInit(onEditHandler?: OnEditHandler) {
        const $newsList = document.querySelectorAll<HTMLElement>(".js-news");
        return [...$newsList].map(($news) => new NewsTile($news, onEditHandler));
    }

    private initEventListeners() {
        this.$root.querySelector<HTMLElement>(`.js-news-edit-button`)
            ?.addEventListener('click', this.onNewsEdit.bind(this));
    }

    private  onNewsEdit() {
        this.onEditHandler?.(this.news);
    }

    private reloadPage() {
        window.location.reload();
    }
}