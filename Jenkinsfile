pipeline {
    agent any 
    stages {
        stage('Stage 1') {
            steps {
                echo 'Hello world!' 
                sh '/home/hansjorgen/Downloads/apictl-3.2.2-linux-x64/apictl/apictl add-env -e dev --apim https://localhost:9443'
                sh '/home/hansjorgen/Downloads/apictl-3.2.2-linux-x64/apictl/apictl export-api -n Strava2 -v 1.0.0 -e dev -k'
                
            }
        }
    }
}
