---
outline: deep
prev:
  text: 'Contribution Guideline'
  link: '/contribute'
---

<script setup>
import { useData } from 'vitepress'
const { site } = useData()
</script>

# Versions

## Versioning Scheme

{{ site.title }} follows [Semantic Versioning 2.0.0].

- **Major** version when you make incompatible API changes
- **Minor** version when you add functionality in a backward compatible manner
- **Patch** version when you make backward compatible bug fixes

When referencing {{ site.title }} from your application or package, 
you should always use a version constraint such as `^1.0`, 
since major releases do include breaking changes. However, 
we strive to always ensure you may update to a new major 
release in one day.

## Report for Duty

### Check if nothing is cooking.

Don't burn yourself and see if someone else also raised the topic 
or maybe even started working on a pull request by [searching 
on GitHub]. If you are unsure or if you have any questions during 
this entire process, please ask your questions in the `#ideas` 
category on [GitHub Discussions].

### Report an issue and wait.

Next, you should report an issue using the [issue tracker] and 
await orders. Seriously you should not begin to code anything 
before you have any warranties that it will be merged into the 
final source code.

You will find a comment (from a maintainer) within the issue, 
that may confirm your request. Otherwise, you will get some 
explanations why we don't support your request.

### Fork the source code.

[Fork] the source code, clone your fork, and configure the remotes:

```bash
git clone https://github.com/<your-username>/symfoniq.git
cd symfoniq/
git remote add upstream https://github.com/sirthxalot/symfoniq.git
```

::: tip 
Replace `<your-username>` with your GitHub account username.
:::

If you cloned a while ago, get the latest changes from upstream:

```bash
git checkout main
git pull upstream main
```

### Create a topic branch.

Each time you want to work on a PR for a bug or on an enhancement, 
create a topic branch:

```bash
git checkout -b <branch-name> main
```

::: tip
Replace `<branch-name>` with your topic branch name. Use a 
descriptive name for your branch e.g. `fix_something`. 
Use words like `feature`, `hotfix`, `fix`,...
:::

Or, if you want to provide a bug fix for the `2.3` branch, first 
track the remote `2.3` branch locally, than create a new branch 
off the `2.3` branch to work on the bug fix:

```bash
git checkout --track origin/2.3
git checkout -b <branch-name> 2.3
```

### Work on your code.

You are now ready to work on the source code and achieve some 
amazing stuff. But keep in mind the following:

- All the code you are going to submit must be released under the MIT license;
- Add unit or feature tests to prove that everything works;
- Try hard to not break backward compatibility (if you must do so, try to provide 
  a compatibility layer to support the old way) -- PRs that break backward 
  compatibility have less chance to be merged;
- Do atomic and logically separate commits (use the power of `git rebase` to have 
  a clean and logical history);
- Never fix coding standards in some existing code as it makes the code review 
  more difficult;
- Write good commit messages: Start by a short subject line (the first line), 
  followed by a blank line and a more detailed description.

## Submit Pull Request

### Rebase your Pull Request.

Before submitting your PR, update your branch (needed if it takes 
you a while to finish your changes):

```bash
git checkout <2.x>
git fetch upstream
git merge upstream/<2.x>
git checkout <branch-name>
git rebase <2.x>
```

::: tip
Replace `<2.x>` with the branch you selected previously (e.g. `2.3`) 
if you are working on a bug fix.
:::

When doing the `rebase` command, you might have to fix merge 
conflicts. `git status` will show you the unmerged files. Resolve 
all the conflicts, then continue the rebase:

```bash
git add ... # add resolved files
git rebase --continue
```

Check that all tests still pass and push your branch remotely:

```bash
git push --force origin <branch-name>
```

### Open a Pull Request.

You can now [make a pull request] on the `sirthxalot/symfoniq` 
GitHub repository.

The default pull request description contains a table which you 
must fill in with the appropriate answers. This ensures that 
contributions may be reviewed without needless feedback loops and 
that your contributions can be included into {{ site.title }} as quickly 
as possible.

<!--                  That's all folks!                 -->

[fork]: https://docs.github.com/de/get-started/quickstart/fork-a-repo
[github discussions]: https://github.com/sirthxalot/symfoniq/discussions/categories/ideas
[issue tracker]: https://github.com/sirthxalot/symfoniq/issues
[make a pull request]: https://github.com/sirthxalot/symfoniq/compare
[searching on github]: https://github.com/sirthxalot/symfoniq/issues?q=+is%3Aopen+
[semantic versioning 2.0.0]: https://semver.org/
