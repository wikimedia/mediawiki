from github import Github
import os

# Use the GitHub token provided by the action
token = os.getenv("GITHUB_TOKEN")
repo_name = os.getenv("GITHUB_REPOSITORY")
pr_number = os.getenv("PR_NUMBER")  # You'd pass this from the workflow

g = Github(token)
repo = g.get_repo(repo_name)
pr = repo.get_pull(int(pr_number))

# Check PR description
has_description = len(pr.body or "") > 20

# Check for .md or docs/ changes
doc_files = [f for f in pr.get_files() if f.filename.endswith('.md') or 'docs/' in f.filename]

# Result
if has_description or doc_files:
    print("PR has documentation.")
else:
    print("No documentation found.")
    exit(1)  # Fail the action
