

# Sonar Mine vs Rock Prediction

This is a machine learning project that uses sonar data to predict whether an object is a mine or a rock. It uses an XGBoost Classifier to make the predictions and a Flask web application to provide a user interface.

## Description

The project is designed to differentiate between mines and rocks based on sonar signals. The model is trained on the "Sonar, Mines vs. Rocks" dataset, which contains sonar returns from different angles. The web interface allows a user to input 6 soundwave values, and the model will predict whether the object is a mine or a rock.

## How It Works

1.  **Data Preprocessing**: The `sonar.py` script reads the sonar data from `sonar data.csv`. The data is split into features (X) and labels (y). The labels 'R' (Rock) and 'M' (Mine) are encoded into numerical values. The data is then split into training and testing sets. StandardScaler is used to scale the features, and PCA is applied for dimensionality reduction.

2.  **Model Training**: An XGBClassifier is trained on the preprocessed data. The trained model is saved as `model.pkl` using pickle.

3.  **Web Interface**: The Flask application (`app.py`) serves the `sonar.html` page. This page contains a form where the user can input 6 soundwave values.

4.  **Prediction**: When the form is submitted, the data is sent to the `/predict` endpoint. The Flask app loads the trained model and uses it to predict whether the object is a mine or a rock. Based on the prediction, it renders either `mine.html` or `rock.html`, which displays the result along with an animation and sound.

## File Descriptions

  * `app.py`: The main Flask application file. It loads the model, handles requests, and makes predictions.
  * `sonar.py`: This script is used for training the machine learning model.
  * `model.pkl`: This file contains the trained XGBoost Classifier model.
  * `sonar data.csv`: The dataset used to train the model.
  * `sonar.html`: The main page of the web application, containing the form for user input.
  * `mine.html`: The page that is displayed when the model predicts a mine.
  * `rock.html`: The page that is displayed when the model predicts a rock.
  * `Alert.mp3`: An audio alert.

## Technologies Used

  * **Python**: The core programming language used.
  * **Flask**: A micro web framework for Python.
  * **Scikit-learn**: A machine learning library for Python.
  * **XGBoost**: An optimized distributed gradient boosting library.
  * **Pandas**: A library for data manipulation and analysis.
  * **Numpy**: A library for numerical computing.
  * **HTML/CSS**: Used for the frontend of the web application.

## Setup and Usage

1.  **Clone the repository:**

    ```
    git clone https://github.com/your-username/Sonar-Mine-vs-Rock-Prediction.git
    ```

2.  **Install the dependencies:**

    ```
    pip install -r requirements.txt
    ```

3.  **Run the Flask application:**

    ```
    python app.py
    ```

4.  **Open your web browser** and go to `http://127.0.0.1:5000/`.

5.  **Enter the 6 soundwave values** in the form and click on "Submit" to get the prediction.
