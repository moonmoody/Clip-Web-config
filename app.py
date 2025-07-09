from flask import Flask, request, jsonify
from flask_cors import CORS
import boto3
import logging

app = Flask(__name__)
CORS(app)
logging.basicConfig(level=logging.INFO)

s3 = boto3.client('s3', region_name='ap-northeast-2')

@app.route('/generate-url', methods=['POST'])
def generate_url():
    key = request.json.get('filename')
    if not key or '..' in key or '/' in key:
        return jsonify({'error': 'Invalid filename'}), 400
    try:
        url = s3.generate_presigned_url(
            'put_object',
            Params={'Bucket': 'clipmarket-images', 'Key': key},
            ExpiresIn=600
        )
        logging.info(f"Generated URL for: {key}")
        return jsonify({'url': url})
    except Exception as e:
        logging.error(f"Error generating URL: {e}")
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8080)
