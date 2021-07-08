pipeline {
    agent any 
    parameters {
        string(name: 'PERSON', defaultValue: 'Mr Jenkins', description: 'Who should I say hello to?')

        text(name: 'BIOGRAPHY', defaultValue: '', description: 'Enter some information about the person')

        booleanParam(name: 'TOGGLE', defaultValue: true, description: 'Toggle this value')

        choice(name: 'CHOICE', choices: ['One', 'Two', 'Three'], description: 'Pick something')

        password(name: 'PASSWORD', defaultValue: 'SECRET', description: 'Enter a password')
    }
    stages {
        stage('Setup parameters') {
            steps {
                script { 
                    properties([
                        parameters([
                            choice(
                                choices: ['ONE', 'TWO'], 
                                name: 'PARAMETER_01'
                            ),
                            booleanParam(
                                defaultValue: true, 
                                description: '', 
                                name: 'BOOLEAN'
                            ),
                            text(
                                defaultValue: '''
                                this is a multi-line 
                                string parameter example
                                ''', 
                                 name: 'MULTI-LINE-STRING'
                            ),
                            string(
                                defaultValue: 'scriptcrunch', 
                                name: 'STRING-PARAMETER', 
                                trim: true
                            ),
                            password(name: 'PASSWORD', defaultValue: 'SECRET', description: 'A secret password')
                        ])
                    ])
                }
            }
        }
        stage('Env') {
            steps {
                sh 'printenv'
                script {
                    env.TEST_VARIABLE = "some test value"
                }
                sh 'printenv'
                echo 'running java'
                sh 'pwd'
                dir ('cd /home/hansjorgen/Downloads/openapi-style-validator-master') {
                    sh 'pwd'
                    sh 'java -jar /home/hansjorgen/Downloads/openapi-style-validator-master/cli/build/libs/openapi-style-validator-cli-1.7-SNAPSHOT-all.jar -s /home/hansjorgen/Downloads/openapi-style-validator-master/specs/petstore.yaml -o /home/hansjorgen/Downloads/openapi-style-validator-master/specs/options.json'
                    echo 'Java ran'
                }
            }
        }
        stage('Stage 1') {
            steps {
                echo "Hello ${params.PARAMETER_01}"
                echo "Hello ${params.UP}"
                echo "Hello ${params.PASSWORD}"
                echo 'Hello world!'
                sh '/home/hansjorgen/Downloads/apictl-3.2.2-linux-x64/apictl/apictl login dev -u admin -p admin -k'
                sh '/home/hansjorgen/Downloads/apictl-3.2.2-linux-x64/apictl/apictl export-api -n Strava2 -v 1.0.0 -e dev -k'
                sh 'cp /home/hansjorgen/.wso2apictl/exported/apis/dev/Strava2_1.0.0.zip /var/lib/jenkins/workspace/hapag/Strava2_1.0.0.zip'
                echo 'File copied'
                sh 'unzip /var/lib/jenkins/workspace/hapag/Strava2_1.0.0.zip'
                echo 'Unzipped'
            }
        }
    }
}
