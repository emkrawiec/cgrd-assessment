import {BasePageObject} from "./BasePageObject";
import {expect, Locator, Page} from "@playwright/test";

export class NewsPageObject extends BasePageObject {
    private readonly titleInput: Locator;
    private contentInput: Locator;
    private submitButton: Locator;
    private headingEl: Locator;
    private contentEl: Locator;

    constructor(page: Page) {
        super(page);
        this.titleInput = page.getByPlaceholder('Title');
        this.contentInput = page.getByPlaceholder('Content');
        this.submitButton = page.locator('data-test-id=news-form-submit-button')
        this.headingEl = page.locator('data-test-id=news-tile-heading');
        this.contentEl = page.locator('data-test-id=news-tile-content');
    }

    async goto() {
        await this.page.goto('http://localhost:8080/news');
    }

    async createNews(title: string, content: string) {
        await this.titleInput.fill(title);
        await this.contentInput.fill(content);
        await this.submitButton.click();
    }

    async assertNewsWithTitleAndContentVisible(title: string, content: string) {
        const headingEl = await this.page.locator('data-test-id=news-tile-heading');
        const contentEl = await this.page.locator('data-test-id=news-tile-content');

        await expect(headingEl).toBeVisible();
        await expect(contentEl).toBeVisible();
        await expect(headingEl).toHaveText(title);
        await expect(contentEl).toHaveText(content);
    }
}