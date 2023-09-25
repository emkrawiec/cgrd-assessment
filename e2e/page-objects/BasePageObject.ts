import {expect, Locator, type Page} from "@playwright/test";

export abstract class BasePageObject {
    protected readonly page;
    private notification: Locator;

    protected constructor(page: Page) {
        this.page = page;
        this.notification = page.getByRole('alert');
    }

    async assertNotificationText(text: string) {
        await expect(this.notification).toHaveText(text);
    }
}