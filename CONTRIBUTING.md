# Contribution Guide

## Getting Started

Welcome to the project! To get started, you will need to install the project dependencies and set up your local environment.

Instructions for installation and set up can be found in the [README.md](README.md) file at the root of the project. Please follow these instructions carefully to ensure that the project is correctly set up and all dependencies are installed.

Once you have completed the installation and set up process, you should be ready to start contributing to the project. Thank you for your interest in helping to improve the project!

## Formatting Commit Messages with Conventional Commits

In this project, we follow the [Conventional Commits](https://www.conventionalcommits.org/) specification for formatting commit messages. This allows us to automate a number of processes, such as generating changelogs.

A commit message consists of a header and, optionally, a body and a footer. The header has a special format that includes a type, a scope, and a subject:

```
<type>(<scope>): <subject>
```

The `type` is a keyword that specifies the kind of change you are committing. Some examples include `feat`, `fix`, and `chore`.

The `scope` is *optional* and specifies the part of the codebase that the commit is related to. For example, `core`, `docs`, or `frontend`.

The `subject` is a brief description of the change. It should be written in imperative present tense and should not exceed 50 characters.

Here is an example of a commit message with all three parts:

```
feat(authentication): add login and logout functionality
```

The *optional* body of the commit message should provide a more detailed description of the change, if necessary. It should be wrapped at 72 characters.

The *optional* footer can be used to reference issues that the commit closes. It should start with the keyword `Closes`, followed by the issue number.

Here is an example of a commit message with a body and a footer:

```
fix(authentication): prevent password reset abuse

Previously, anyone with the password reset link could change the
password of any user. This commit adds a one-time token to the password
reset process, so that the link can only be used once.

Closes #123
```

## Code Quality and Continuous Integration

In order to ensure the quality of the codebase, we use a number of tools for continuous integration. These tools should be run locally before committing changes in order to detect and fix issues.

To run all the continuous integration tools at once, you can use the following command:

```
make fix
```

This command will run PHP CS Fixer, PHP CodeSniffer, and PHPStan in sequence, and will fix any issues that can be automatically resolved.

In addition to these tools, we also use SymfonyInsight to verify the quality of the codebase. SymfonyInsight is a continuous integration platform that runs a number of checks on every pull request as well as additional checks specific to Symfony projects.

## Testing with PHPUnit

We use PHPUnit for unit testing in this project. All pull requests must include tests for any new or modified code. The tests should be run automatically as part of the continuous integration process.

To run the tests, you can use the following command:

```
make tests
```

Please make sure that all tests pass before submitting your pull request. If you are fixing a bug, please include a test that reproduces the error and verifies that it is fixed.
