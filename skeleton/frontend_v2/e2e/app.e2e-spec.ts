import { SkillsPage } from './app.po';

describe('skills App', () => {
  let page: SkillsPage;

  beforeEach(() => {
    page = new SkillsPage();
  });

  it('should display welcome message', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('Welcome to app!!');
  });
});
