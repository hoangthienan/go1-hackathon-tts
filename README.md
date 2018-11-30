# TTS

Find and rename `config.sample.php` to `config.php`, then edit the file and add your AWS, S3 information

### Speak text
`php speak_text.php`
```
{
    "text": "Hi! My name is Emma. Welcome to the Amazon Polly demo.",
    "s3_url": "https://gvn-hackathon.s3.ap-southeast-1.amazonaws.com/audio/demo.mp3\n"
}
```
OR
`php speak_text.php?instance={instanceId}&loId={loId}`
```
{
    "text": "Idea The text to speech feature automatically transforms your text based learning content into interactive and engaging content that learners will love.\n\nThis feature uses advanced artificial intelligence machine learning techniques to take text in GO1 resources and transform it into near perfect audio of your content.\n\nLearners can then choose how they want to learn - they can read the text or listen to the content being read to them, giving them more options on how they learn. \n\nObviously we could tell you all about this feature - but we are so confident in what we have built here that we thought we would make the whole presentation a live demo. So sit back and learn via GO1’s new text to speech feature how this idea works and the benefits.  ",
    "s3_url": "https://gvn-hackathon.s3.ap-southeast-1.amazonaws.com/audio/instance-103116-loid-748669.mp3"
}
```
### Speak ssml
`php speak_ssml.php`