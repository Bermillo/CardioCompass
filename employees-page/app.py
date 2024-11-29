from flask import Flask, request, jsonify
import pickle
import csv
from io import StringIO

app = Flask(__name__)
try:
    with open('./employees-page/best_model_2.pkl', 'rb') as model_file:
        model = pickle.load(model_file)
except Exception as e:
    print(f"Error loading model: {e}")
    exit(1)

@app.route('/predict', methods=['POST'])
def heart_disease_screening():
    data = request.get_json()

    if not data:
        return jsonify({'error': 'Invalid or missing JSON'}), 400

    required_fields = ['age', 'sex', 'chest_pain_type', 'resting_blood_pressure', 
                       'cholesterol', 'fasting_blood_sugar', 'rest_ecg', 'max_heart_rate', 
                       'exercise_angina', 'ST_depression', 'ST_slope']
    missing_fields = [field for field in required_fields if field not in data or not data[field]]
    if missing_fields:
        return jsonify({'error': f'Please fill in all the fields. Missing: {", ".join(missing_fields)}'}), 400
    
    try:
        features = [
            int(data.get('age')),
            int(data.get('sex')),
            int(data.get('chest_pain_type')),
            int(data.get('resting_blood_pressure')),
            int(data.get('cholesterol')),
            int(data.get('fasting_blood_sugar')),
            int(data.get('rest_ecg', 1)),
            int(data.get('max_heart_rate')),
            int(data.get('exercise_angina')),
            float(data.get('ST_depression')),
            int(data.get('ST_slope'))
        ]
        
        prediction = model.predict([features])
        risk = 'low' if prediction[0] == 0 else 'high'
        
        return jsonify({'prediction': risk})
    
    except Exception as e:
        return jsonify({'error': f'Error during prediction: {str(e)}'}), 500

@app.route('/predict-from-file', methods=['POST'])
def predict_from_file():
    if 'file' not in request.files:
        return jsonify({'error': 'No file provided'}), 400
    
    file = request.files['file']
    
    try:
        file_contents = StringIO(file.read().decode('utf-8'))
        csv_reader = csv.DictReader(file_contents)
        
        predictions = []
        required_fields = ['age', 'sex', 'chest_pain_type', 'resting_blood_pressure', 
                           'cholesterol', 'fasting_blood_sugar', 'rest_ecg', 'max_heart_rate', 
                           'exercise_angina', 'ST_depression', 'ST_slope']
        
        for row_number, row in enumerate(csv_reader, start=1):
            missing_fields = [field for field in required_fields if field not in row or not row[field]]
            if missing_fields:
                predictions.append({'error': f'Missing fields in row {row_number}: {", ".join(missing_fields)}'})
                continue

            features = [
                int(row['age']),
                int(row['sex']),
                int(row['chest_pain_type']),
                int(row['resting_blood_pressure']),
                int(row['cholesterol']),
                int(row['fasting_blood_sugar']),
                int(row['rest_ecg']),
                int(row['max_heart_rate']),
                int(row['exercise_angina']),
                float(row['ST_depression']),
                int(row['ST_slope'])
            ]
            
            prediction = model.predict([features])
            risk = 'low' if prediction[0] == 0 else 'high'
            
            row['prediction'] = risk
            predictions.append(row)
        
        return jsonify(predictions)
    
    except Exception as e:
        return jsonify({'error': f'Error processing the file: {str(e)}'}), 500

if __name__ == '__main__':
    app.run(debug=True)