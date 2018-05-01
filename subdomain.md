# Deployment Mapping

## Mapping `dev` deployment

Create the deployment server named `dev`. Be sure to mark this server as default upload server. Map the directories as follows. 

### Hassib

| local directory | server directory |
| :--- | :--- |
| `hassib_html` | `hassib` |
| `public_html/assets` | `hassib\assets` |
| `gradebookApp` | `hassib\gradebookApp` |

### Manuel

| local directory | server directory |
| :--- | :--- |
| `manuel_html` | `manuel` |
| `public_html/assets` | `manuel\assets` |
| `gradebookApp` | `manuel\gradebookApp` |

### Mitchell

| local directory | server directory |
| :--- | :--- |
| `mitchell_html` | `mitchell` |
| `public_html/assets` | `mitchell\assets` |
| `gradebookApp` | `mitchell\gradebookApp` |

Be sure to ignore the local directory `dev_html/assets/scss`, otherwise intermediary sass files may be pushed to the server.

## Mapping `live` deployment

Create the deployment server named `live`. Map the directories as follows. 

| local directory | server directory |
| :--- | :--- |
| `public_html` | `public_html` |
| `gradebookApp` | `gradebookApp` |