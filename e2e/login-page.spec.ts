import {test, expect} from '@playwright/test';
import {LoginPageObject} from "./page-objects/LoginPageObject";

test('User can login into app using correct credentials.', async ({page}) => {
    // given
    const pageObject = new LoginPageObject(page);
    await pageObject.goto();

    // when
    await pageObject.fillForm('admin', 'admin');
    await pageObject.submitForm();

    // then
    await expect(page).toHaveURL('http://localhost:8080/news');
});

test('User can see error notification if provided incorrect credentials.', async ({page}) => {
    // given
    const pageObject = new LoginPageObject(page);
    await pageObject.goto();

    // when
    await pageObject.fillForm('admin', 'admin');
    await pageObject.submitForm();

    // then
    await pageObject.assertNotificationText('Invalid username or password');
});