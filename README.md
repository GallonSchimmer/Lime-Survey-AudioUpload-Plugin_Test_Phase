# Lime-Survey-AudioUpload-Plugin_Test_Phase

## Important Notes

- **Testing Environment:** Use a local server (e.g., XAMPP) for testing, debugging, and development.
- **Production Warning:** Do not use a production server for testing, as plugins may disrupt the system if they do not work as expected.

- ![File handling Enable](https://github.com/GallonSchimmer/Lime-Survey-AudioUpload-Plugin_Test_Phase/assets/26065891/b4b5dcb4-adcd-4c6f-906e-96676545ba93)


## Overview

Integrating an audio player, a file upload field, and a hidden input field into the Lime Survey question:

This Lime Survey plugin, named `AudioUploadQuestion`, will allow users to upload audio files. The plugin includes a File Handling Module that activates an "Enable audio upload" option in the Lime Survey question settings.

### Lime Survey Environment

- Lime Survey Community Edition Version: 3.28.76+231018
- Local Server: Apache (XAMPP)
- PHP Version: 5.5.9 to 7.4.x

## Installation

1. Download Lime Survey Server from [https://community.limesurvey.org/downloads/](https://community.limesurvey.org/downloads/).
2. Choose the LTS Release 3.28.76.
3. Support end: End of June 2023 (supports PHP 5.5.9 to 7.4.x).

## Plugin Installation

1. Clone or download the repository to your local Lime Survey plugins directory.
   - Path: `C:\xampp\htdocs\limesurvey\plugins\AudioUploadQuestion\`

2. Activate the plugin in the Lime Survey administration interface.

## PHP Extensions Configuration

Ensure the following PHP extensions are enabled in your `php.ini` file:

```ini
extension=gd
extension=imap
extension=ldap
```

## Database Configuration

1. Create a new database table for storing uploaded audio data:

```sql
USE limesurvey_audioplayerquestion;

DROP TABLE IF EXISTS audio_uploads;

CREATE TABLE audio_uploads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    audio_url VARCHAR(255) NOT NULL
);
```

2. Configure database credentials in the plugin files:

   - `upload.php`
   - `AudioUploadQuestion.php`

## Usage

1. After activating the plugin, a new question type, "Audio Upload," will be available.
2. In the Lime Survey question settings, enable the "Enable audio upload" option in the "File Handling" module.
3. Use the provided HTML and JavaScript to integrate the audio player and file upload field into your Lime Survey questions.



## Code Snippets

### upload.php (Relative Path: `C:\xampp\htdocs\limesurvey\myproject\upload.php`)


This PHP snippet is a part of a file upload handling process and includes steps to move an uploaded audio file to a specified destination and provides database credentials. Let's break down each part:

1. **File Upload Handling:**
    ```php
    if ($_FILES['audioUpload']['error'] == UPLOAD_ERR_OK) {
    ```
    - This line checks if there are no errors during the file upload. `UPLOAD_ERR_OK` is a constant indicating that the file was uploaded successfully.

2. **File Information:**
    ```php
    $tmp_name = $_FILES['audioUpload']['tmp_name'];
    $name = $_FILES['audioUpload']['name'];
    ```
    - These lines retrieve information about the uploaded file. `$tmp_name` holds the temporary filename on the server, and `$name` contains the original name of the file on the client's machine.

3. **Destination Path:**
    ```php
    $destination = "uploads/$name";
    ```
    - This line sets the destination path for the uploaded file. The file will be moved to a directory named "uploads" with the original file name.

4. **Move Uploaded File:**
    ```php
    move_uploaded_file($tmp_name, $destination);
    ```
    - This line moves the uploaded file from its temporary location (`$tmp_name`) to the specified destination (`$destination`). This is a crucial step in the file upload process.

5. **Database Credentials:**
    ```php
    // Database credentials
    $host = 'localhost';
    $port = '3306';
    $db   = 'limesurvey_audioplayerquestion';
    $user = 'your_username';
    $pass = 'your_password';
    $charset = 'utf8mb4';
    ```
    - These lines define variables holding the database connection credentials, including the host, port, database name, username, password, and character set.

This snippet primarily handles the successful upload of an audio file and prepares to store the file information in a database. It assumes that the destination directory exists and that appropriate security measures have been implemented to prevent unauthorized access or execution. Additionally, it establishes the database credentials required for later interactions with the database.


### AudioUploadQuestion.php (Relative Path: `C:\xampp\htdocs\limesurvey\plugins\AudioUploadQuestion\AudioUploadQuestion.php`)


#This PHP snippet is a part of the `AudioUploadQuestion` class, which is a Lime Survey plugin extending the `PluginBase` class. Let's break down the key components:

1. **Class Declaration:**
    ```php
    class AudioUploadQuestion extends PluginBase {
    ```
    - This line declares a new class named `AudioUploadQuestion` that extends the `PluginBase` class. This means that `AudioUploadQuestion` inherits properties and methods from `PluginBase`.

2. **Protected Property:**
    ```php
    protected $storage = 'DbStorage';
    ```
    - This line declares a protected property named `$storage` and assigns it the value `'DbStorage'`. This property likely indicates the type of storage used by the plugin, and in this case, it suggests that the plugin might interact with a database for storage.

3. **Static Protected Properties:**
    ```php
    static protected $description = 'An audio upload question type';
    static protected $name = 'AudioUploadQuestion';
    ```
    - These lines declare static protected properties. Static properties belong to the class rather than an instance of the class. Here, `$description` holds a description for the plugin, and `$name` stores the name of the plugin.

4. **Constructor (`init` method):**
    ```php
    public function init() {
        $this->subscribe('beforeQuestionRender');
        $this->subscribe('newQuestionAttributes');
    ```
    - The `init` method is a constructor-like method that is automatically called when an instance of the class is created. Inside the `init` method:
        - `$this->subscribe('beforeQuestionRender');` subscribes the plugin to the Lime Survey event system for the event `beforeQuestionRender`. This means that the plugin will be notified and can perform actions before a question is rendered.
        - `$this->subscribe('newQuestionAttributes');` subscribes the plugin to the event `newQuestionAttributes`, indicating that the plugin wants to be notified when new question attributes are being set.

In summary, this PHP snippet defines the `AudioUploadQuestion` class, sets some properties related to the plugin, and subscribes the plugin to specific Lime Survey events. These events allow the plugin to perform actions at key points in the Lime Survey question rendering process.


##HTML and JavaScript (part of the `AudioUploadQuestion` class)

Generating HTML and JavaScript code that integrates an audio player, a file upload field, and a hidden input field into the Lime Survey question's HTML. Let's break down the key components:

1. **Audio Player:**
    ```html
    <audio controls><source src="' . $audioUrl . '" type="audio/mpeg"></audio>
    ```
    - This line creates an HTML5 audio player with playback controls. The `src` attribute is dynamically set using the `$audioUrl` variable, which contains the URL of the uploaded audio file.

2. **File Upload Field:**
    ```html
    <input type="file" id="audioUpload" name="audioUpload">
    ```
    - This line adds an input field of type "file," allowing users to choose and upload an audio file. The `id` is set to "audioUpload" for easy identification in JavaScript.

3. **Hidden Input Field for Question ID:**
    ```html
    <input type="hidden" id="qid" name="qid" value="' . $event->get('qid') . '">
    ```
    - This line creates a hidden input field (`type="hidden"`) to store the question ID. The value is set dynamically using the Lime Survey event system (`$event->get('qid')`).

4. **Upload Button and JavaScript Function:**
    ```html
    <button onclick="uploadAudio()">Upload</button>
    ```
   
    ```javasctipt
    function uploadAudio() {
        var fileInput = document.getElementById("audioUpload");
        var file = fileInput.files[0];
        var qid = document.getElementById("qid").value;
        var formData = new FormData();
        formData.append("audioUpload", file);
        formData.append("qid", qid);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "http://localhost/limesurvey/myproject/upload.php", true);
        xhr.send(formData);
    }
   ````
   
    
    - This section adds a "Upload" button to trigger the file upload process. The button is associated with a JavaScript function named `uploadAudio()`.
    - The `uploadAudio()` function retrieves the selected audio file, the question ID, and creates a `FormData` object to prepare data for the HTTP request.
    - An XMLHttpRequest is then used to send a POST request to the server-side script (`upload.php`) responsible for handling the file upload.

Overall, this code snippet enhances the Lime Survey question by allowing users to upload audio files. The HTML and JavaScript create a user-friendly interface with an audio player and a file upload button, while the associated script handles the asynchronous file upload process to the server.


### config.xml (for LS4: https://manual.limesurvey.org/Make_your_plugin_compatible_with_LS4)

This XML snippet represents the configuration file (`config.xml`) for a Lime Survey plugin named "Audio Upload Question." Here's an explanation of the different sections within the XML:

1. **XML Declaration:**
    ```xml
    <?xml version="1.0" encoding="UTF-8" ?>
    ```
    - This line specifies the XML version and character encoding used in the file.

2. **Plugin Root Element:**
    ```xml
    <plugin>
    ```
    - This element marks the beginning of the XML document for the Lime Survey plugin configuration.

3. **Plugin Metadata:**
    - `<name>`: Specifies the name of the plugin.
    - `<type>`: Indicates the type of the plugin (e.g., "question" in this case).
    - `<creationDate>`: Shows the date when the plugin was created.
    - `<lastUpdate>`: Indicates the last update date of the plugin.
    - `<author>`: Contains information about the author, including name, URL, and email.
    - `<version>`: Specifies the version number of the plugin.
    - `<license>`: Specifies the license under which the plugin is released (e.g., GPL).
    - `<description>`: Provides a brief description of the plugin's functionality.
    - `<supportUrl>`: Specifies the URL where users can find support for the plugin.
    - `<compatibility>`: Indicates the Lime Survey version compatibility, e.g., compatible with version 3.x.

4. **Updater Information:**
    - `<updaters>`: Contains information about plugin updaters.
        - `<updater>`: Specifies details about a specific updater.
            - `<version>`: Specifies the version number of the updater.
            - `<url>`: Provides the URL where the updater file (`update.xml`) can be found.

5. **Configuration Settings:**
    - `<config>`: This section includes configuration settings for the plugin.
        - `<setting>`: Defines an individual configuration setting.
            - `<name>`: Specifies the name of the setting.
            - `<type>`: Specifies the data type of the setting (e.g., "integer" in this case).
            - `<default>`: Specifies the default value for the setting.
            - `<label>`: Provides a user-friendly label for the setting.
            - `<help>`: Offers a description or help text for the setting.

This XML file is crucial for defining the metadata, version information, and configuration settings of the Lime Survey plugin. It is used by Lime Survey to understand how the plugin should be integrated and behave within the Lime Survey environment. Plugin developers customize this file based on the specific requirements and features of their plugins.
