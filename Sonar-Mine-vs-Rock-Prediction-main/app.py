from flask import Flask, request, url_for, redirect, render_template
import pickle
import numpy as np

app = Flask(__name__)

model = pickle.load(open('model.pkl', 'rb'))


@app.route('/')
def hello_world():
    return render_template("sonar.html")


@app.route('/predict', methods=['POST', 'GET'])
def predict():
    features = [float(x) for x in request.form.values()]
    final_data = [np.array(features)]
    prediction = model.predict(final_data)
    output = prediction
    if output == 0:
        return render_template('mine.html')
    else:
        return render_template('rock.html')


if __name__ == '__main__':
    app.run(debug=True)
