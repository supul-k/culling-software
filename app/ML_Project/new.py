import keras
from keras.preprocessing.image import array_to_img, img_to_array, load_img
import numpy as np
from keras.models import model_from_json
import cv2
from skimage import io
from skimage.draw import rectangle_perimeter
# import dlib
import numpy as np
import os
import shutil



good_dir = "D:\culling\culling-software\storage\\app\public\ML_Project\\good\\"
bad_dir = "D:\culling\culling-software\storage\\app\public\ML_Project\\bad\\"

model_path = 'D:\culling\culling-software\\app\ML_Project\\blurry_cnn_model_saved'

# Load the model architecture from JSON
with open("D:\culling\culling-software\\app\ML_Project\model3.json", "r") as json_file:
    loaded_model_json = json_file.read()
model = model_from_json(loaded_model_json)

# Load the model weights
model.load_weights("D:\culling\culling-software\\app\ML_Project\model3.h5")

print("Model loaded from disk.")

cnn = keras.models.load_model(model_path)

def predict_image_cnn(path):
    img = load_img(path, target_size=(224, 224))
    img = img_to_array(img)

    img = img/255
    img = np.expand_dims(img, axis=0)
    predict = cnn.predict(img)
    return predict

# detector = dlib.get_frontal_face_detector()
# predictor = dlib.shape_predictor("shape_predictor_68_face_landmarks.dat")

def get_eye_of_image(path):
    image = cv2.imread(path)
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

    # Detect faces in the image
    faces = detector(gray)

    eyes = []
    # Iterate over detected faces
    for face in faces:
        # Determine the facial landmarks for the face region
        shape = predictor(gray, face)
        landmarks = [(shape.part(i).x, shape.part(i).y) for i in [17,18,19,20,21,36,37,38,39,40,41]]

        # Extract the eye region bounding box from landmarks
        x1 = min(landmarks, key=lambda x: x[0])[0]+10
        y1 = min(landmarks, key=lambda x: x[1])[1]+10
        x2 = max(landmarks, key=lambda x: x[0])[0]+10
        y2 = max(landmarks, key=lambda x: x[1])[1]+10

        # Extract the eye region from the image with tresholding
        if y2-y1 > x2-x1:
          margin = y2-y1-x2+x1
          eye = image[y1:y2, x1-int(margin/2):x2+int(margin/2)]
        elif y2-y1 < x2-x1:
          margin = x2-x1-y2+y1
          eye = image[y1-int(margin/2):y2+int(margin/2), x1:x2]

        eyes.append(eye)

    return eyes



def predict_blink(path):
    blinks = [0,0]
    eyes = get_eye_of_image(path)
    for eye in eyes:
        img_ = cv2.resize(eye, (224,224))
        img_ = np.expand_dims(img_, axis=0)


        prediction = model.predict(img_)

        
        if prediction[0][0] > prediction[0][1]:
            blinks[0] += 1

        else:
            blinks[1] += 1

    return blinks

def get_cull_image(path):
    blur = predict_image_cnn(path)
    # blinks = predict_blink(path)

    return blur

default_dir_path = 'D:\culling\culling-software\storage\\app\ML_Project\images\\'

def sort_blurry(dir_path):

    for file in os.listdir(dir_path):

        file_path = dir_path + str(file)

        blur = get_cull_image(file_path)

        if blur < 0.5:
            shutil.copy(file_path, good_dir)
        else:
            shutil.copy(file_path, bad_dir)

    return 1

sort_blurry(default_dir_path)