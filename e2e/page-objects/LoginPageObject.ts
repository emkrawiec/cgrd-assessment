import {type Locator, type Page} from "@playwright/test";
import {BasePageObject} from "./BasePageObject";

export class LoginPageObject extends BasePageObject {
    private static readonly LOGIN_URL = 'http://localhost:8080/login';
    protected readonly page: Page;
    private readonly loginButton: Locator;
    private readonly usernameInput: Locator;
    private readonly passwordInput: Locator;

    constructor(page: Page) {
        super(page);
        this.page = page;
        this.usernameInput = page.getByPlaceholder('Username')
        this.passwordInput = page.getByPlaceholder('Password');
        this.loginButton = page.getByText('Login');
    }

    async fillForm(username: string, password: string) {
        await this.usernameInput.fill(username);
        await this.passwordInput.fill(password);
    }

    async submitForm() {
        await this.loginButton.click();
    }

    async goto() {
        await this.page.goto(LoginPageObject.LOGIN_URL);
    }

    static async authorizePage(page: Page) {
        const pageObject = new LoginPageObject(page);
        await pageObject.goto();
        await pageObject.fillForm('admin', 'admin');
        await pageObject.submitForm();

        return page;
    }
}