pipeline {
    agent any 
    stages {
        stage('Stage 1') {
            steps {
                echo 'Hello world!' 
                sh './apictl export-api -n Strava2 -v 1.0.0 -e dev -k'
            }
        }
    }
}
