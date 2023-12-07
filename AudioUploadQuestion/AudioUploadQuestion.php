<?php
class AudioUploadQuestion extends PluginBase {
    protected $storage = 'DbStorage';
    static protected $description = 'An audio upload question type';
    static protected $name = 'AudioUploadQuestion';

    public function init() {
        $this->subscribe('beforeQuestionRender');
        $this->subscribe('newQuestionAttributes');
		
    }
	
	public function newQuestionAttributes() {
    $event = $this->getEvent();

    $questionAttributes = array(
        'audioUpload' => array(
            'types' => 'S', // 'S' is the code for 'Short Free Text'
            'category' => gT('File handling'),
            'sortorder' => 100,
            'inputtype' => 'switch',
            'default' => 0,
            'help' => gT('Enable audio file upload for this question.'),
            'caption' => gT('Enable audio upload')
        )
    );

    $event->set('questionAttributes', $questionAttributes);
}

	public function beforeQuestionRender() {
    $event = $this->getEvent();
    $attributes = $event->get('question')->attributes;

    if (!isset($attributes['audioUpload']) || $attributes['audioUpload'] != 1) {
        return;
    }

    // Get the URL of the uploaded audio file from the database
    $audioUrl = $this->getAudioUrl($event->get('qid'));

    // Add an audio player, a file upload field, and a hidden input field for the question ID to the question's HTML
    $uploadHTML = '
    <audio controls><source src="' . $audioUrl . '" type="audio/mpeg"></audio>
    <input type="file" id="audioUpload" name="audioUpload">
    <input type="hidden" id="qid" name="qid" value="' . $event->get('qid') . '">
    <button onclick="uploadAudio()">Upload</button>

    <script>
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
    </script>';

    // Get the question text
    $questionText = $event->get('text');

    // Append the upload HTML to the question text
    $questionText .= $uploadHTML;

    // Set the question text
    $event->set('text', $questionText);
}

	public function getAudioUrl($qid) {
    // Database credentials
    $host = 'localhost';
    $port = '3306';
    $db   = 'limesurvey_audioplayerquestion';
    $user = 'root';
    $pass = 'root';
    $charset = 'utf8mb4';

    // DSN (Data Source Name)
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";

    // Create a new PDO instance
    $pdo = new PDO($dsn, $user, $pass);

    // Write the SQL query
    $sql = "SELECT audio_url FROM audio_uploads WHERE question_id = ?";

    // Prepare the SQL statement
    $stmt = $pdo->prepare($sql);

    // Execute the SQL statement, passing the question ID as a parameter
    $stmt->execute([$qid]);

    // Fetch the result
    $result = $stmt->fetch();

    // If fetch returned a result, return the audio URL
    if ($result) {
        return $result['audio_url'];
    }

    // If fetch didn't return a result, return an empty string
    return '';
}

    
}