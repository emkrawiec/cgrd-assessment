import {News} from "../model/news";

type State = {
    type: 'CREATE';
} | {
    type: 'EDIT';
    news: News;
}

export class NewsForm {
    private static EDIT_FORM_CLASS = 'is-in-edit-mode';
    private $form: HTMLFormElement;
    private $heading: HTMLFormElement;
    private $submitButton: HTMLButtonElement;
    private $contentInput: HTMLTextAreaElement;
    private $titleInput: HTMLInputElement;
    private $idInput: HTMLInputElement;
    private $methodInput: HTMLInputElement;
    private $abortEditModeButton: HTMLFormElement;
    private _state: State;
    constructor($rootElement: HTMLFormElement) {
        this.$form = $rootElement;
        this.$heading = this.$form.querySelector<HTMLElement>('.js-news-form-heading') as HTMLFormElement;
        this.$titleInput = this.$form.querySelector<HTMLInputElement>('.js-news-form-title-input') as HTMLInputElement;
        this.$contentInput = this.$form.querySelector<HTMLTextAreaElement>('.js-news-form-content-input') as HTMLTextAreaElement;
        this.$idInput = this.$form.querySelector<HTMLInputElement>('.js-news-form-id-input') as HTMLInputElement;
        this.$methodInput = this.$form.querySelector<HTMLInputElement>('.js-news-form-method-input') as HTMLInputElement;
        this.$abortEditModeButton = this.$form.querySelector<HTMLElement>('.js-news-form-abort-edit-mode') as HTMLFormElement;
        this.$submitButton = this.$form.querySelector<HTMLButtonElement>('.js-news-form-submit') as HTMLButtonElement;
        this._state = {
            type: 'CREATE'
        }

        this.initEventListeners();
    }

    public static autoInit() {
        const $newsForm = document.querySelector<HTMLFormElement>(".js-news-form");
        if ($newsForm) {
            return new NewsForm($newsForm);
        } else {
            throw new Error('News form not found!');
        }
    }

    public requestEditForm(news: News) {
        this.state = {
            type: 'EDIT',
            news: news
        }
    }

    private initEventListeners() {
        this.$abortEditModeButton.addEventListener('click', this.onAbortEditMode.bind(this));
    }

    private handleEditStateFormUi(news: News) {
        this.$heading.textContent = 'Edit news';
        this.$form.classList.add(NewsForm.EDIT_FORM_CLASS);
        this.$submitButton.textContent = 'Save';
        this.$abortEditModeButton.hidden = false;
        this.$titleInput.value = news.title.trim();
        this.$contentInput.value = news.content.trim();
        this.$idInput.value = news.id.toString();
        this.$methodInput.value = 'PUT';
    }

    private handleCreateStateFormUi() {
        this.$heading.textContent = 'Create news';
        this.$form.classList.remove(NewsForm.EDIT_FORM_CLASS);
        this.$submitButton.textContent = 'Create';
        this.$titleInput.value = '';
        this.$contentInput.value = '';
        this.$abortEditModeButton.hidden = true;
        this.$idInput.value = '';
        this.$methodInput.value = '';
    }

    private onAbortEditMode() {
        this.state = {
            type: 'CREATE'
        }
    }

    private get state(): State {
        return this._state;
    }

    private set state(value: State) {
        this._state = value;
        if (value.type === 'CREATE') {
            this.handleCreateStateFormUi();
        } else {
            this.handleEditStateFormUi(value.news);
        }
    }
}