import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.preprocessing import LabelEncoder
from sklearn.decomposition import PCA
from xgboost import XGBClassifier
import pickle

dataset = pd.read_csv('Copy of sonar data.csv', header=None)

X = dataset.iloc[:, :-1].values
y = dataset.iloc[:, -1].values

le = LabelEncoder()
y = le.fit_transform(y)

X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=1)

sc = StandardScaler()
X_train = sc.fit_transform(X_train)
X_test = sc.transform(X_test)

pca = PCA(n_components=6)
X_train = pca.fit_transform(X_train)
X_test = pca.transform(X_test)

classifier = XGBClassifier()
classifier.fit(X_train, y_train)

pickle.dump(classifier,open('model.pkl', 'wb'))
model = pickle.load(open('model.pkl', 'rb'))

