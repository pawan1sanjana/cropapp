from flask import Flask, render_template, request, jsonify

app = Flask(__name__)

@app.route('/')
def index():
    return render_template('ph.html')  # Serves the HTML frontend

@app.route('/calculate', methods=['POST'])
def calculate():
    try:
        # Retrieve data from the frontend
        ph = float(request.form['ph'])
        land_area = float(request.form['land_area'])
        area_unit = request.form['area_unit']

        # Convert land area to acres
        if area_unit == "hectares":
            land_area_acres = land_area * 2.47105  # 1 hectare = 2.47105 acres
        elif area_unit == "perches":
            land_area_acres = land_area * 0.0252929  # 1 perch = 0.0252929 acres
        else:  # acres
            land_area_acres = land_area

        # Determine dolomite rate based on pH
        if ph < 3.9:
            dolomite_rate = 1000  # kg per acre
        elif 3.9 <= ph < 4.2:
            dolomite_rate = 800
        elif 4.2 <= ph < 4.5:
            dolomite_rate = 600
        else:  # pH >= 4.5
            dolomite_rate = 400

        # Calculate total dolomite
        total_dolomite = dolomite_rate * land_area_acres

        # Return results as JSON
        return jsonify({
            "rate": f"{dolomite_rate} kg/ac",
            "total": f"{total_dolomite:.2f} kg"
        })

    except ValueError:
        return jsonify({"error": "Invalid input. Please enter numeric values."}), 400

if __name__ == '__main__':
    app.run(debug=True)