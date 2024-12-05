import pandas as pd
from flask import Flask, request, jsonify, render_template
import numpy as np
import csv
from io import StringIO
from sklearn.ensemble import RandomForestClassifier
import joblib
from sklearn.preprocessing import StandardScaler

app = Flask(__name__)

# Load the model and scaler
try:
    model = joblib.load('./employees-page/rf_model.joblib')
    scaler = joblib.load('./employees-page/scaler.joblib')
except Exception as e:
    print(f"Error loading model: {e}")
    exit(1)

# Define mappings for categorical values
categorical_mappings = {
    'Yes': 1,
    'No': 0,
    'No, borderline diabetes': 0,
    'Yes (during pregnancy)': 1,
    'Male': 1,
    'Female': 0,
    'Poor': 0,
    'Fair': 1,
    'Good': 2,
    'Very good': 3,
    'Excellent': 4,
    'White': 0,
    'Black': 1,
    'Hispanic': 2,
    'Asian': 3,
    'American Indian/Alaskan Native': 4,
    'Other': 5,
    '18-24': 0,
    '25-29': 1,
    '30-34': 2,
    '35-39': 3,
    '40-44': 4,
    '45-49': 5,
    '50-54': 6,
    '55-59': 7,
    '60-64': 8,
    '65-69': 9,
    '70-74': 10,
    '75-79': 11,
    '80 or older': 12
}

@app.route('/') 
def home():
    return render_template('home-page.php')
@app.route('/predict1', methods=['POST'])
def predict():
    try:
        # Get the data from the request
        data = request.get_json()
        if not data:
            return jsonify({'error': 'No data provided'}), 400
        
        # Define required fields
        required_fields = ['bmi', 'smoking', 'alcoholdrinking', 'stroke', 'physicalhealth', 
                           'mentalhealth', 'diffwalking', 'sex', 'agecategory', 'race', 'diabetic',
                           'physicalactivity', 'genhealth', 'sleeptime', 'asthma', 'kidneydisease', 'skincancer']
        
        # Check for missing fields
        missing_fields = [field for field in required_fields if field not in data or not data[field]]
        if missing_fields:
            return jsonify({'error': f'Missing fields: {", ".join(missing_fields)}'}), 400
        
        # Prepare feature data
        features = [
            float(data.get('bmi')),  # Convert BMI to float
            int(data.get('smoking')),  # Convert to integer
            int(data.get('alcoholdrinking')),
            int(data.get('stroke')),
            float(data.get('physicalhealth')),
            float(data.get('mentalhealth')),
            int(data.get('diffwalking')),
            int(data.get('sex')),
            int(data.get('agecategory')),
            int(data.get('race')),
            int(data.get('diabetic')),
            int(data.get('physicalactivity')),
            int(data.get('genhealth')),
            int(data.get('sleeptime')),
            int(data.get('asthma')),
            int(data.get('kidneydisease')),
            int(data.get('skincancer'))
        ]
        
        # Convert features to numpy array and reshape for the model
        features = np.array(features).reshape(1, -1)
        
        # Scale the features using the pre-trained scaler
        features2 = scaler.transform(features)
        
        # Print the features for debugging
        print("Features:", features2) 
        
        # Predict using the model
        prediction = model.predict(features2)
        
        # Print the prediction for debugging
        print("Prediction:", prediction)  
        
        # Map prediction to risk label
        risk = "Low" if prediction == 0 else "High"
        
        # Return the prediction as JSON
        return jsonify({'prediction': risk})

    except Exception as e:
        # Handle any errors that occur during prediction
        return jsonify({'error': f'Error during prediction: {str(e)}'}), 500

@app.route('/predict1-from-file', methods=['POST'])
def predict_from_file():
    if 'file' not in request.files:
        return jsonify({'error': 'No file provided'}), 400
    
    file = request.files['file']
    
    if not (file.filename.endswith('.csv') or file.filename.endswith('.xlsx')):
        return jsonify({'error': 'Invalid file type. Please upload a CSV or XLSX file.'}), 400
    
    try:
        if file.filename.endswith('.csv'):
            # Read the contents of the uploaded CSV file
            file_contents = StringIO(file.read().decode('utf-8'))
            csv_reader = csv.DictReader(file_contents)
            data = list(csv_reader)
        else:
            # Read the contents of the uploaded XLSX file
            df = pd.read_excel(file)
            data = df.to_dict(orient='records')

        # Check if CSV/XLSX file headers are valid
        required_fields = ['bmi', 'smoking', 'alcoholdrinking', 'stroke', 'physicalhealth', 
                           'mentalhealth', 'diffwalking', 'sex', 'agecategory', 'race', 'diabetic',
                           'physicalactivity', 'genhealth', 'sleeptime', 'asthma', 'kidneydisease', 'skincancer']
        
        if not data or not all(field in data[0] for field in required_fields):
            return jsonify({'error': 'CSV file headers do not match the required fields'}), 400
        
        predictions = []

        # Process each row in the file
        for row_number, row in enumerate(data, start=1):
            missing_fields = [field for field in required_fields if field not in row or not row[field]]
            if missing_fields:
                predictions.append({'error': f'Missing fields in row {row_number}: {", ".join(missing_fields)}'})
                continue

            try:
                # Prepare feature data
                features = [
                    float(row['bmi']),
                    categorical_mappings.get(row['smoking'], row['smoking']),
                    categorical_mappings.get(row['alcoholdrinking'], row['alcoholdrinking']),
                    categorical_mappings.get(row['stroke'], row['stroke']),
                    float(row['physicalhealth']),
                    float(row['mentalhealth']),
                    categorical_mappings.get(row['diffwalking'], row['diffwalking']),
                    categorical_mappings.get(row['sex'], row['sex']),
                    categorical_mappings.get(row['agecategory'], row['agecategory']),
                    categorical_mappings.get(row['race'], row['race']),
                    categorical_mappings.get(row['diabetic'], row['diabetic']),
                    categorical_mappings.get(row['physicalactivity'], row['physicalactivity']),
                    categorical_mappings.get(row['genhealth'], row['genhealth']),
                    float(row['sleeptime']),
                    categorical_mappings.get(row['asthma'], row['asthma']),
                    categorical_mappings.get(row['kidneydisease'], row['kidneydisease']),
                    categorical_mappings.get(row['skincancer'], row['skincancer'])
                ]
            except ValueError as e:
                predictions.append({'error': f'Invalid data in row {row_number}: {str(e)}'})
                continue

            # Scale the features using the pre-trained scaler
            features = np.array(features).reshape(1, -1)
            features_scaled = scaler.transform(features)

            # Make the prediction using the model
            prediction = model.predict(features_scaled)
            risk = 'low' if prediction[0] == 0 else 'high'
            
            # Add the prediction to the row
            row['prediction'] = risk
            predictions.append(row)
        
        # Return the predictions as JSON
        return jsonify(predictions)

    except Exception as e:
        # Handle any errors during the processing of the file
        return jsonify({'error': f'Error processing the file: {str(e)}'}), 500

if __name__ == '__main__':
    app.run(debug=True)