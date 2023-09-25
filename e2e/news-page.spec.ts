import {test, expect} from '@playwright/test';
import {LoginPageObject} from "./page-objects/LoginPageObject";
import {NewsPageObject} from "./page-objects/NewsPageObject";

test.only('User can create and see new news.', async ({page}) => {
    // given
    await LoginPageObject.authorizePage(page);
    const newsPageObject = new NewsPageObject(page);

    // when
    await newsPageObject.goto();
    await newsPageObject.createNews('Test title', 'Test content');


    // then
    await newsPageObject.assertNewsWithTitleAndContentVisible('Test title', 'Test content');
});