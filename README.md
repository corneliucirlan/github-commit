# github-commit

Get the last GitHub commit of a specific user

## Usage

```php
$commit = new GithubLastCommit('username', 'GITBUB_CLIENT_ID', 'GITHUB_CLIENT_SECRET');
$commit = $commit->getLastCommit();
```
