import '../css/style.css';

const isNewsPage = document.body.classList.contains('js-page-news');
if (isNewsPage) {
    Promise.all([
        import('./components/NewsForm'),
        import('./components/NewsTile'),
    ])
        .then(([{ NewsForm }, { NewsTile }]) => {
            const newsForm = NewsForm.autoInit();
            NewsTile.autoInit((news) => {
                newsForm.requestEditForm(news);
            });
        });
}

